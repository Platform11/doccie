const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    purge: [
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: false,
    theme: {
        colors: {
            orange: colors.orange,
            gray: colors.coolGray,
            yellow: colors.yellow,
            purple: colors.violet,
            blue: colors.blue,
            green: colors.green,
            red: colors.red,
        },
        extend: {
            fontFamily: {
                sans: ["Inter var", ...defaultTheme.fontFamily.sans],
            },
            padding: {
                5: "1.25rem",
                7: "1.8750rem",
                11: "2.75rem",
                14: "3.5rem",
                22: "5.5rem",
                68: "18rem",
                70: "19rem",
                72: "20rem",
            },
            borderRadius: {
                xl: "0.8375rem",
                xl2: "1rem",
            },
            height: {
                11: "2.75rem",
                14: "3.5rem",
                22: "5.5rem",
                128: "24rem",
                map: "calc(100%+2.75rem)",
            },
            width: {
                14: "3.5rem",
                128: "24rem",
            },
            colors: {
                primary: {
                    light: "#4CBBA3",
                    DEFAULT: "#3AAC93",
                    dark: "#2B9F86",
                    transparent: "rgba(58, 172, 147, .05)",
                },
                danger: {
                    light: "#FF2D60",
                    DEFAULT: "#D11D33",
                    dark: "#EC164A",
                    transparent: "rgba(255, 42, 93, .1)",
                },
                white: "#FFFFFF",
                black: "#000000",
            },
            maxWidth: {
                "8xl": "80rem",
                "10xl": "88rem",
            },
            screens: {
                standalone: {
                    raw: "(max-width: 576px) and (display-mode: standalone)",
                },
            },
        },
    },

    variants: {
        opacity: ["responsive", "hover", "focus", "disabled"],
        ringOpacity: ["hover", "focus", "active"],
    },

    plugins: [],
};
