function multipleScreenPopup(
  url,
  title,
  w,
  h,
  print = 0,
  centered = true,
  moveRight = 0,
  moveDown = 0,
  resizable = "yes"
) {
  // Fixes dual-screen position                         Most browsers      Firefox
  var dualScreenLeft =
    window.screenLeft != undefined ? window.screenLeft : screen.left;
  var dualScreenTop =
    window.screenTop != undefined ? window.screenTop : screen.top;

  var width = window.innerWidth
    ? window.innerWidth
    : document.documentElement.clientWidth
    ? document.documentElement.clientWidth
    : screen.width;
  var height = window.innerHeight
    ? window.innerHeight
    : document.documentElement.clientHeight
    ? document.documentElement.clientHeight
    : screen.height;

  var left = 0;
  var top = 0;
  if (centered === true) {
    left = width / 2 - w / 2 + dualScreenLeft;
    top = height / 2 - h / 2 + dualScreenTop;
  } else {
    left = dualScreenLeft + moveRight;
    top = dualScreenTop + moveDown;
  }
  var newWindow = window.open(
    url,
    title,
    "directories=no,titlebar=no,toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=" +
      resizable +
      ", width=" +
      w +
      ", height=" +
      h +
      ", top=" +
      top +
      ", left=" +
      left
  );

  //  }
  if (print == 1) {
    window.onload = function () {
      newWindow.print();
      setTimeout(function () {
        newWindow.close();
      }, 5000);
    };
    //                newWindow.onpageshow = function() {newWindow.print();}
  } else {
    // Puts focus on the newWindow
    if (window.focus) {
      newWindow.focus();
    }
  }
}

function ord(val, field, form = 0) {
  document.forms[form].elements[field].value = val;
  document.forms[form].submit();
  return false;
}

function Myloader(doc = this.document, stop = 0) {
  object = doc.getElementById("content");
  if (object) {
    if (stop == 1) {
      object.style.display = "block";
      object2 = doc.getElementById("loader");
      object2.style.display = "none";
    } else {
      object.style.display = "none";
      object2 = doc.getElementById("loader");
      object2.style.display = "block";
    }
  }
}

function convertNumber(myNumber) {
  re = /,/;
  myNumber = myNumber.replace(re, ".");
  myNumber = parseInt(myNumber);
  if (isNaN(myNumber)) {
    return 0;
  } else {
    return myNumber;
  }
}
