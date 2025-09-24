const defaultTheme = require('tailwindcss/defaultTheme');
const yiiConf = require('./webpack-yii.json');
const sourcePath = yiiConf.sourceDir + '/' + yiiConf.subDirectories.sources
const colors = require('tailwindcss/colors');
module.exports = {
    darkMode: 'class',
    content: [
        sourcePath + '/app/**/*.{html,ts}',
        sourcePath + '/**/*.{html,ts,tsx,js,jsx}',
        './src/views/**/*.php',
        './src/widgets/views/*.php',
    ],
    plugins: [require('@tailwindcss/forms', {
        // strategy: 'base', // only generate global styles
        // strategy: 'class', // only generate classes
    })],
};