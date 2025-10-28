<?php
/**
 * Template for displaying single posts.
 *
 * @package cb-identity2025
 */

defined( 'ABSPATH' ) || exit;
get_header();

?>
<main id="main" class="case-study has-neutral-100-background-color">
    <?php
    the_content();
    ?>
</main>
<?php
get_footer();
?>