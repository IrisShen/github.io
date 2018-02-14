// var myHeading = document.querySelector('h1');
// myHeading.textContent = 'Hello World!';
// document.querySelector('html').onclick = function() {
//     alert('Ouch! Stop poking me!');
// }

var myImage = document.querySelector('img');

myImage.onclick = function(){
    var mySrc = myImage.getAttribute('src');
    if(mySrc === 'images/Beijing.jpg'){
        myImage.setAttribute('src','images/flower.jpg');
    } else {
        myImage.setAttribute('src','images/Beijing.jpg');
    }
}

var myButton = document.querySelector('button');
var myHeading = document.querySelector('h1');

function setUserName() {
    var myName = prompt('Please enter your name.');
    localStorage.setItem('name', myName);
    myHeading.textContent = 'Welcome my friend, ' + myName;
  }

  if(!localStorage.getItem('name')) {
    setUserName();
  } else {
    var storedName = localStorage.getItem('name');
    myHeading.textContent = 'Welcome my friend, ' + storedName;
  }

  myButton.onclick = function() {
    setUserName();
  }