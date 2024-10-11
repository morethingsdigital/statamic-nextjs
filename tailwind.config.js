/** @type {import('tailwindcss').Config} */
module.exports = {
  prefix: "sn-",
  content: [
    "./resources/**/*.antlers.html",
    "./resources/**/*.antlers.php",
    "./resources/**/*.blade.php",
    "./resources/**/*.vue",
    "./resources/**/*.{ts,tsx,js,jsx}",
    "./content/**/*.md",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
