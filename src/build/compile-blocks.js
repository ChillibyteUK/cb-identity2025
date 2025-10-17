const fs = require('fs');
const path = require('path');
const sass = require('sass');

/**
 * Compile block-specific SCSS files
 */
function compileBlocks() {
    const blocksDir = path.resolve(__dirname, '../../blocks');
    
    if (!fs.existsSync(blocksDir)) {
        console.log('Blocks directory not found');
        return;
    }
    
    const blockFolders = fs.readdirSync(blocksDir).filter(item => {
        return fs.statSync(path.join(blocksDir, item)).isDirectory();
    });
    
    console.log(`Found ${blockFolders.length} block folders`);
    
    blockFolders.forEach(blockName => {
        const blockDir = path.join(blocksDir, blockName);
        const scssFile = path.join(blockDir, `${blockName}.scss`);
        const cssOutput = path.join(blockDir, `${blockName}.css`);
        
        if (fs.existsSync(scssFile)) {
            try {
                const result = sass.compile(scssFile, {
                    style: 'expanded',
                    sourceMap: true
                });
                
                // Write CSS file
                fs.writeFileSync(cssOutput, result.css);
                
                // Write source map
                if (result.sourceMap) {
                    fs.writeFileSync(`${cssOutput}.map`, JSON.stringify(result.sourceMap));
                }
                
                console.log(`✓ Compiled ${blockName}.scss`);
            } catch (error) {
                console.error(`✗ Error compiling ${blockName}.scss:`, error.message);
            }
        }
    });
}

// Run compilation if this script is executed directly
if (require.main === module) {
    compileBlocks();
}

module.exports = compileBlocks;