function togglePasswordSesion() {
    var passwordInput = document.getElementById("pass");
    var icon = document.getElementById("PassIcon");

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      icon.innerText = "visibility";
      showPasswordCheckbox.checked = true;
    } else {
      passwordInput.type = "password";
      icon.innerText = "visibility_off";
      showPasswordCheckbox.checked = false;
    }
  }

function togglePasswordReg1() {
    var passwordInput1 = document.getElementById("passw");
    var icon1 = document.getElementById("PassIcon1");

    if (passwordInput1.type === "password") {
      passwordInput1.type = "text";
      icon1.innerText = "visibility";
      showPasswordCheckbox.checked = true;
    } else {
      passwordInput1.type = "password";
      icon1.innerText = "visibility_off";
      showPasswordCheckbox.checked = false;
    }
  }
  
function togglePasswordReg2() {
    var passwordInput2 = document.getElementById("confpassw");
    var icon2 = document.getElementById("PassIcon2");

    if (passwordInput2.type === "password") {
      passwordInput2.type = "text";
      icon2.innerText = "visibility";
      showPasswordCheckbox.checked = true;
    } else {
      passwordInput2.type = "password";
      icon2.innerText = "visibility_off";
      showPasswordCheckbox.checked = false;
    }
  }

  function togglePasswordReg3() {
    var passwordInput2 = document.getElementById("epass");
    var icon2 = document.getElementById("PassIcon3");

    if (passwordInput2.type === "password") {
      passwordInput2.type = "text";
      icon2.innerText = "visibility";
      showPasswordCheckbox.checked = true;
    } else {
      passwordInput2.type = "password";
      icon2.innerText = "visibility_off";
      showPasswordCheckbox.checked = false;
    }
  }