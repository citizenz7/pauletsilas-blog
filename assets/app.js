// import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import "./styles/fonts.css";
import "./styles/variables.css";
import "./styles/app.css";

/* burger menu responsive */
const burger = document.querySelector(".burger");
const nav = document.querySelector(".mobile");

burger.addEventListener("click", () => {
  nav.classList.toggle("active");
});

console.log("This log comes from assets/app.js - welcome to AssetMapper! 🎉");
