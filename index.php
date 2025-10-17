<?php
/**
 * Template for displaying the blog index page.
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;

$page_for_posts = get_option( 'page_for_posts' );

get_header();
?>
<main id="main">
	<?php
	// Display ACF blocks and content from the "page for posts".
	if ( $page_for_posts ) {
		// Get the page for posts object.
		$posts_page = get_post( $page_for_posts );

		if ( $posts_page && $posts_page->post_content ) {
			// Set up global $post for ACF and content functions.
			global $post;
			$original_post = $post;
			$post          = $posts_page; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			setup_postdata( $post );

			// Output the page content (which includes ACF blocks).
			echo apply_filters( 'the_content', $posts_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			// Restore original post data.
			$post = $original_post; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			wp_reset_postdata();
		}
	}
	?>
    <section class="latest_posts mt-5">
        <div class="container pb-5">
			<h3>Search</h3>
            <?php
            // Get all categories for filter buttons.
            $all_categories = get_categories(
				array(
					'hide_empty' => true,
					'orderby'    => 'name',
					'order'      => 'ASC',
				)
			);

            if ( ! empty( $all_categories ) ) {
                ?>
                <div class="row mb-4">
                    <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                        <div class="position-relative">
                            <input type="text" class="form-control" id="search-input" placeholder="Search posts..." autocomplete="off">
                            <div id="search-suggestions" class="search-suggestions position-absolute w-100 bg-white border rounded shadow-sm mt-1" style="display: none; z-index: 1000; max-height: 300px; overflow-y: auto;"></div>
                        </div>
                    </div>
                    <div class="col-6 col-lg-4 mb-3 mb-lg-0">
                        <select class="form-select filter-select" id="category-filter" data-filter-type="category">
                            <option value="all" selected>All Categories</option>
                            <?php
							foreach ( $all_categories as $category ) {
								?>
                                <option value="<?= esc_attr( $category->slug ); ?>"><?= esc_html( $category->name ); ?></option>
                            	<?php
							}
							?>
                        </select>
                    </div>
                    <div class="col-6 col-lg-4">
                        <select class="form-select filter-select" id="year-filter" data-filter-type="year">
                            <option value="all" selected>All Years</option>
                            <?php
                            // Get all unique post years.
                            global $wpdb;
							// phpcs:ignore WordPress.DB.DirectDatabaseQuery
                            $years = $wpdb->get_col( "SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC" );
                            foreach ( $years as $yr ) {
                                ?>
                                <option value="<?= esc_attr( $yr ); ?>"><?= esc_html( $yr ); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="row g-4">
            <?php
            // Custom query to include both published and scheduled posts.
            $args = array(
                'post_type'      => 'post',
                'post_status'    => array( 'publish', 'future' ), // Include published and scheduled posts.
                'orderby'        => 'date',
                'order'          => 'DESC', // Descending order.
                'posts_per_page' => -1,    // Get all posts.
            );

            $q = new WP_Query( $args );

            if ( $q->have_posts() ) {
				while ( $q->have_posts() ) {
					$q->the_post();
					// get categories.
					$categories = get_the_category();
					if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
						// get space separated list of category slugs.
						$first_category = $categories[0];
						// If there are multiple categories, use the first one.
						if ( count( $categories ) > 1 ) {
							// Get the first category slug.
							$categories = array_slice( $categories, 0, 1 );
						}
						// Convert to space separated list.
						$categories = implode( ' ', wp_list_pluck( $categories, 'slug' ) );
					}

					$plink  = get_permalink();
					$target = '_self';
					if ( 'research' === $first_category->slug ) {
						$plink  = wp_get_attachment_url( get_field( 'pdf', get_the_ID() ) );
						$target = '_blank';
					}
					if ( 'video' === $first_category->slug || 'podcast' === $first_category->slug ) {
						$video_link = get_field( 'video_link', get_the_ID() );
						if ( $video_link && ! ( str_contains( $video_link, 'youtube.com' ) || str_contains( $video_link, 'vimeo.com' ) ) ) {
							$plink  = $video_link;
							$target = '_blank';
						}
					}
					?>
					<div class="col-md-6 col-lg-4" data-category="<?= esc_attr( $categories ); ?>" data-year="<?= esc_attr( get_the_date( 'Y' ) ); ?>">
						<a href="<?= esc_url( $plink ); ?>" target="<?= esc_attr( $target ); ?>" class="latest-insights__item">
							<div class="latest-insights__img-wrapper">
								<div class="category <?= esc_attr( $first_category->slug ); ?>">// <?= esc_html( $first_category->name ); ?></div>
								<?= get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'img-fluid' ) ); ?>
							</div>
							<div class="latest-insights__inner">
								<h3><?= esc_html( get_the_title() ); ?></h3>
								<div><?= esc_html( get_the_excerpt() ); ?></div>
							</div>
							<div class="read-more">Read More</div>
						</a>
					</div>
					<?php
                }
            } else {
                echo '<p>No posts found.</p>';
            }

            // Reset post data.
            wp_reset_postdata();
            ?>
            </div>
        </div>
    </section>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterSelects = document.querySelectorAll('.filter-select');
    const searchInput = document.getElementById('search-input');
    const searchSuggestions = document.getElementById('search-suggestions');
    const postsContainer = document.querySelector('.row.g-4');
    const originalPosts = postsContainer.innerHTML; // Store original posts

    let searchTimeout;
    let abortController;

    // Client-side filtering for category/year when no search
    function filterExistingPosts() {
        const posts = document.querySelectorAll('[data-category]');
        const categoryFilter = document.getElementById('category-filter').value;
        const yearFilter = document.getElementById('year-filter').value;
        
        posts.forEach(post => {
            const postCategories = post.getAttribute('data-category');
            const postYear = post.getAttribute('data-year');
            
            const categoryMatch = categoryFilter === 'all' || (postCategories && postCategories.includes(categoryFilter));
            const yearMatch = yearFilter === 'all' || postYear === yearFilter;
            
            if (categoryMatch && yearMatch) {
                post.style.display = 'block';
            } else {
                post.style.display = 'none';
            }
        });
    }

    // AJAX search function
    function performAjaxSearch() {
        const searchQuery = searchInput.value.trim();
        const categoryFilter = document.getElementById('category-filter').value;
        const yearFilter = document.getElementById('year-filter').value;

        // If no search term, use client-side filtering
        if (!searchQuery) {
            postsContainer.innerHTML = originalPosts;
            filterExistingPosts();
            return;
        }

        // Cancel previous AJAX request
        if (abortController) {
            abortController.abort();
        }

        // Create new abort controller
        abortController = new AbortController();

        // Show loading state
        postsContainer.innerHTML = '<div class="col-12 text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        // Perform AJAX search
        fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'search_posts',
                search_term: searchQuery,
                category: categoryFilter,
                year: yearFilter,
                nonce: '<?php echo esc_attr( wp_create_nonce( 'post_search_nonce' ) ); ?>'
            }),
            signal: abortController.signal
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                postsContainer.innerHTML = data.data.html;
                // Reinitialize AOS for new elements
                if (typeof AOS !== 'undefined') {
                    AOS.refresh();
                }
            } else {
                postsContainer.innerHTML = '<div class="col-12"><p class="text-center">Error loading search results.</p></div>';
            }
        })
        .catch(error => {
            if (error.name !== 'AbortError') {
                console.error('Search error:', error);
                postsContainer.innerHTML = '<div class="col-12"><p class="text-center">Error loading search results.</p></div>';
            }
        });
    }

    // Search input with debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performAjaxSearch();
        }, 500);
    });

    // Filter selects - trigger search if there's a search term, otherwise filter client-side
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            const searchQuery = searchInput.value.trim();
            if (searchQuery) {
                performAjaxSearch();
            } else {
                filterExistingPosts();
            }
        });
    });

    // Clear search
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            this.value = '';
            postsContainer.innerHTML = originalPosts;
            filterExistingPosts();
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.position-relative')) {
            searchSuggestions.style.display = 'none';
        }
    });
});
</script>

<?php

get_footer();
?>