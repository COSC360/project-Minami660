window.addEventListener('load', () => {
  document.getElementById("mainForm").addEventListener("submit", submitForm);

});

function submitForm(){
  if((document.getElementById("title").value == "" || document.getElementById("body").value == "" || document.getElementById("tag").value == "" )){
    alert("All entries must be filled.");
  }
}