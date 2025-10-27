<?php
/**
 * CB Case Study Hero Block Template
 *
 * @package  cb-identity2025
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="case-study-hero has-primary-black-background-color py-5">
<div class="id-container pt-5">
    <h1 ><?= get_the_title(); ?></h1>
    <h2>
        <?= get_field( 'case_study_subtitle' ); ?>
    </h2>
</div>
</section>
