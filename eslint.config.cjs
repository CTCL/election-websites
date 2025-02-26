const js = require("@eslint/js");

const {
    FlatCompat,
} = require("@eslint/eslintrc");

const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

module.exports = [{
    ignores: ["assets/js/**.min.js", "assets/js/**.src.js"],
}, ...compat.extends("wordpress"), {
    languageOptions: {
        ecmaVersion: 8,
        sourceType: "module",

        parserOptions: {
            ecmaFeatures: {
                jsx: true,
            },
        },
    },

    rules: {
        camelcase: ["error", {
            properties: "never",
        }],

        yoda: ["error", "always", {
            onlyEquality: true,
        }],

        "space-in-parens": ["error", "always", {
            exceptions: ["empty"],
        }],

        eqeqeq: ["error"],
        indent: ["error", "tab"],
    },
}];
