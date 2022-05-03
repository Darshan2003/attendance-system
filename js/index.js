const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');


const toggleEye = () => {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    togglePassword.classList.toggle('bi-eye');
}


const displayMsg = (msg) => {
    const info = document.getElementById('info');
    info.children[0].children[0].innerHTML = msg;
    info.style.display = 'block';
    setTimeout(() => {
        info.style.display = 'none';
    }, 3500);
}



const filter = (status) => {
    let input = document.getElementById("search").value.toUpperCase();
    let table = document.getElementById("myTable");
    let tr = table.getElementsByTagName("tr");
    for (i = 0; i < tr.length; i++) {
        Mname = tr[i].getElementsByTagName("td")[0];
        Mid = tr[i].getElementsByTagName("td")[1];
        Mstatus = tr[i].getElementsByTagName("td")[4];
       
        if (Mname || Mid || Mstatus) {
            nameValue = Mname.textContent || Mname.innerText;
            idValue = Mid.textContent || Mid.innerText;
            statusValue = status? (Mstatus.textContent || Mstatus.innerText):'';
            if ((nameValue.toUpperCase().indexOf(input) > -1) || (idValue.toUpperCase().indexOf(input) > -1) || ((status === 'status') && (statusValue.toUpperCase().indexOf(input) > -1))) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }

        
    }
}


const exportExcel = ($filename = "table-export.csv") => {

    const dataTable = document.getElementById("myTable");
    const exporter = new TableCSVExporter(dataTable);
    const csvOutput = exporter.convertToCSV();
    const csvBlob = new Blob([csvOutput], { type: "text/csv" });
    const blobUrl = URL.createObjectURL(csvBlob);
    const anchorElement = document.createElement("a");

    anchorElement.href = blobUrl;
    anchorElement.download = $filename;
    anchorElement.click();

    setTimeout(() => {
        URL.revokeObjectURL(blobUrl);
    }, 500);
}