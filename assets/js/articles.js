import "../styles/articles.css";

/* Select tri catégories */
// document.querySelector("select").addEventListener("change", function () {
//   var selectedOption = this.options[this.selectedIndex];
//   if (selectedOption.value) {
//     window.location.href = selectedOption.value;
//   }
// });

document.addEventListener("DOMContentLoaded", function () {
  let selects = document.querySelectorAll("select");
  selects.forEach(function (select) {
    select.addEventListener("change", function (event) {
      let selectedOption = event.target.options[event.target.selectedIndex];
      if (selectedOption.value) {
        window.location.href = selectedOption.value;
      }
    });
  });
});
