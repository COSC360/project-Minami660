window.addEventListener('load', () => {
  document.getElementById("mainForm").addEventListener("submit", submitForm);

});

function submitForm(){
  if (document.getElementById("form2Example1").value=="" || document.getElementById("form2Example2").value==""){
    alert("All entries must be filled.");
  }
}