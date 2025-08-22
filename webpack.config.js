const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const fs = require('fs');

module.exports = {
    entry: {
        main: './assets/js/main.js',
        styles: './assets/scss/main.scss',
        ...getBlockEntries('./assets/blocks')
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'assets/dist'),
        clean: true,
    },
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader', 'sass-loader'],
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: { loader: 'babel-loader', options: { presets: ['@babel/preset-env'] } },
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin({ filename: '[name].css' }),
    ],
    mode: 'development',
    devtool: 'source-map',
    watch: true,
};

function getBlockEntries(blocksDir) {
    const entries = {};
    const root = path.resolve(__dirname, blocksDir);

    function walk(dir) {
        fs.readdirSync(dir).forEach((name) => {
            const full = path.join(dir, name);
            const stat = fs.statSync(full);

            if (stat.isDirectory()) {

                const indexJs = path.join(full, 'index.js');
                if (fs.existsSync(indexJs)) {
                    const blockName = path.basename(full);
                    const entryName = `block_${blockName}`;
                    entries[entryName] = `./${path.relative(__dirname, indexJs).replace(/\\/g, '/')}`;
                }

                walk(full);
            }
        });
    }

    walk(root);
    return entries;
}
