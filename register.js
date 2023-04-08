window.addEventListener('load', () => {
  document.getElementById("mainForm").addEventListener("submit", submitForm);

});

function submitForm(){
  if(document.getElementById("form2Example1").value=="" || document.getElementById("form2Example2").value=="" || document.getElementById("form2Example3").value=="" ){
    alert("All entries must be filled.");
  } else if(document.getElementById("form2Example2").value.length<=6){
    alert("Password has to be longer than 6 characters.");
  } else if (!document.getElementById("form2Example1").value.includes("@")){
    alert("Your email address has to include @.");
  }
}
