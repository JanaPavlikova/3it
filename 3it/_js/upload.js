var fileobj;

function upload_file_import(e, typ) {
  e.preventDefault();
  fileobj = e.dataTransfer.files[0];
  ajax_file_upload_import(fileobj, typ);
}

function file_explorer_import(typ) {
  document.getElementById("selectfile_import").click();
  document.getElementById("selectfile_import").onchange = function () {
    fileobj = document.getElementById("selectfile_import").files[0];
    ajax_file_upload_import(fileobj, typ);
  };
}

function ajax_file_upload_import(file_obj, typ) {
  if (file_obj != undefined) {
    var form_data = new FormData();
    form_data.append("file", file_obj);
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../upload_import.php?typ=" + typ, true);
    xhttp.onload = function (event) {
      oOutput = document.querySelector(".import-content");
      if (xhttp.status == 200) {
        let str = this.responseText;
        let myind = str.lastIndexOf("/");
        str = str.substr(myind + 1);
        if (myind >= 0) {
          document.RE.elements["myFileImport"].value = str;
          oOutput.innerHTML = "Soubor nahrán jako: " + str;
          parent.document.getElementById("ImportButton").className = "myhead";
          parent.document.getElementById("ImportButton").onclick = function () {
            Myloader();
            document.RE.submit();
          };
        } else {
          document.RE.elements["myFileImport"].value = "";
          oOutput.innerHTML = "Soubor nelze nahrát nebo je špatného formátu! ";
        }
        //            parent.document.getElementById('ImportButton').onclick = document.RE.submit();
      } else {
        oOutput.innerHTML =
          "Chyba " + xhttp.status + " při pokusu o nahrání Vašeho souboru.";
      }
    };

    xhttp.send(form_data);
  }
}
