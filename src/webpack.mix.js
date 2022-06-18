const mix = require('laravel-mix')
const path = require('path')
const CompressionPlugin = require('compression-webpack-plugin')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.ts('resources/js/app.ts', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css')
    .version()
    .webpackConfig({
        resolve: {
            alias: {
                '@': [
                    path.resolve('resources/sass'),
                    path.resolve('resources/js')
                ],
            }
        },
        plugins: [
            new CompressionPlugin({
                filename: '[path].gz[query]',
                algorithm: 'gzip',
                test: /\.js$|\.css$|\.html$|\.svg$/,
                threshold: 10240,
                minRatio: 0.8,
            })
        ],
    })
