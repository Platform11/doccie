const mix = require("laravel-mix");
const path = require("path");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("postcss-nested"), // or require('postcss-nesting')
        require("autoprefixer")
    ])
    .vue({ version: 2 })
    .webpackConfig({
        devServer: {
            https: true
        },
        output: {
            chunkFilename: "js/[name].js?id=[chunkhash]",
            publicPath: mix.inProduction() ? "/" : "https://localhost:8080/"
        }
    })
    .copyDirectory("resources/images/**", "public/images")
    .copyDirectory("resources/fonts/**", "public/fonts")
    .sourceMaps();

mix.browserSync({
    proxy: "https://doccie.test",
    https: true,
    ghostMode: false,
    files: ["resources/lang/**/*.php", "resources/views/**/*.php"]
});

mix.override(webpackConfig => {
    webpackConfig.resolve.modules = [
        "node_modules",
        __dirname + "/vendor/spatie/laravel-medialibrary-pro/resources/js"
    ];
});

mix.alias({
    "@": path.resolve("resources/js")
});

if (mix.inProduction()) {
    mix.version();
}
