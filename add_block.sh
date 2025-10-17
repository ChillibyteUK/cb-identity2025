#!/bin/bash

# Add block script
# Usage: ./add_block.sh

# Prompt for block display name
if [ $# -eq 0 ]; then
    echo "Enter the block display name (e.g., 'Hero Banner', 'Call to Action'):"
    read -r BLOCK_DISPLAY_NAME
else
    BLOCK_DISPLAY_NAME=$1
fi

# Check if display name was provided
if [ -z "$BLOCK_DISPLAY_NAME" ]; then
    echo "Error: Block display name is required"
    exit 1
fi

# Convert display name to block name (lowercase, spaces to hyphens, remove special chars)
BLOCK_NAME=$(echo "$BLOCK_DISPLAY_NAME" | tr '[:upper:]' '[:lower:]' | sed 's/[^a-z0-9 ]//g' | sed 's/ /-/g' | sed 's/--*/-/g' | sed 's/^-\|-$//g')
BLOCK_SLUG="${BLOCK_NAME//-/_}"
BLOCK_DIR="blocks/$BLOCK_NAME"

echo "Creating block: $BLOCK_DISPLAY_NAME"
echo "Block name: $BLOCK_NAME"
echo "Block slug: $BLOCK_SLUG"
echo "Block directory: $BLOCK_DIR"
echo ""

# Create block directory
mkdir -p "$BLOCK_DIR"


# Get package name from style.css
PACKAGE_NAME=$(grep -m1 'Theme Name:' style.css | sed 's/Theme Name:[[:space:]]*//')

# Create PHP file
cat > "$BLOCK_DIR/$BLOCK_NAME.php" << 'PHP_END'
<?php
/**
 * BLOCK_DISPLAY_NAME Block Template
 *
 * @package PACKAGE_NAME
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Block ID.
$block_id = $block['id'] ?? '';

?>
<section id="<?php echo esc_attr( $block_id ); ?>" class="BLOCK_CSSS_CLASS">

</section>
PHP_END


# Replace only intended placeholders (not PHP variable names)
sed -i "s/BLOCK_DISPLAY_NAME/$BLOCK_DISPLAY_NAME/g" "$BLOCK_DIR/$BLOCK_NAME.php"
sed -i "s/BLOCK_SLUG/$BLOCK_SLUG/g" "$BLOCK_DIR/$BLOCK_NAME.php"
sed -i "s/BLOCK_CSS_CLASS/$BLOCK_NAME/g" "$BLOCK_DIR/$BLOCK_NAME.php"
sed -i "s/PACKAGE_NAME/$PACKAGE_NAME/g" "$BLOCK_DIR/$BLOCK_NAME.php"

echo "Created PHP file: $BLOCK_DIR/$BLOCK_NAME.php"

# Create SCSS file
cat > "$BLOCK_DIR/$BLOCK_NAME.scss" << SCSS_END
/**
 * $BLOCK_DISPLAY_NAME Block Styles
 *
 * @package $PACKAGE_NAME
 */

SCSS_END

echo "Created SCSS file: $BLOCK_DIR/$BLOCK_NAME.scss"

# Create block.json
cat > "$BLOCK_DIR/block.json" << BLOCK_JSON_END
{
    "name": "cb/$BLOCK_NAME",
    "title": "$BLOCK_DISPLAY_NAME",
    "description": "A custom $BLOCK_DISPLAY_NAME block",
    "category": "cb-blocks",
    "icon": "admin-generic",
    "keywords": ["$BLOCK_NAME", "cb"],
    "supports": {
        "align": ["wide", "full"],
        "anchor": true,
        "customClassName": true
    },
    "acf": {
        "mode": "edit",
        "renderTemplate": "$BLOCK_NAME.php"
    },
    "style": "file:./$BLOCK_NAME.css"
}
BLOCK_JSON_END

echo "Created block.json: $BLOCK_DIR/block.json"

# Create ACF fields JSON with initial message field
cat > "$BLOCK_DIR/group_$BLOCK_SLUG.json" << ACF_JSON_END
{
    "key": "group_$BLOCK_SLUG",
    "title": "$BLOCK_DISPLAY_NAME",
    "fields": [
        {
            "key": "field_${BLOCK_SLUG}_message",
            "label": "$BLOCK_DISPLAY_NAME",
            "name": "message",
            "type": "message",
            "instructions": "",
            "required": 0,
            "conditional_logic": 0,
            "wrapper": {
                "width": "",
                "class": "",
                "id": ""
            },
            "message": "",
            "new_lines": "wpautop",
            "esc_html": 0
        }
    ],
    "location": [
        [
            {
                "param": "block",
                "operator": "==",
                "value": "cb/$BLOCK_NAME"
            }
        ]
    ],
    "menu_order": 0,
    "position": "normal",
    "style": "default",
    "label_placement": "top",
    "instruction_placement": "label",
    "hide_on_screen": "",
    "active": true,
    "description": "Fields for the $BLOCK_DISPLAY_NAME block",
    "show_in_rest": 0
}
ACF_JSON_END

echo "Created ACF fields: $BLOCK_DIR/group_$BLOCK_SLUG.json"

echo ""
echo "Block created successfully!"
echo "Next steps:"
echo "1. Run 'npm run css' to compile the SCSS"
echo "2. Refresh WordPress admin to see the new ACF field group"
echo "3. Edit the ACF fields in WordPress admin under Custom Fields"
echo "4. Edit the block files to add your custom functionality"
