

async function deleteProduct(tr, id) {
    let payload = new FormData();
    payload.append("operation", "product.delete");
    payload.append("id", id);
    
    console.log(id);

    let overlay = document.querySelector("#table-container .overlay");
    overlay.classList.remove("hide");

    await fetch("backend.php", {
        method : "POST",
        body : payload
    })
    .then(r => r.json())
    .then(r => {
        if ( r.msg == "success" ) {
            tr.remove();
            setTimeout(() => {
                overlay.classList.add("hide");
            }, 1000);
            console.log(r);
        }
    });
}

async function add() {
    let modal = document.querySelector("#addProductModal");
    let name = modal.querySelector("input[name='name']").value;
    let quantity = modal.querySelector("input[name='quantity']").value;
    let description = modal.querySelector("textarea[name='description']").value;
    let image = modal.querySelector("input[name='image']");
    let category_id = [...modal.querySelectorAll(".category")];
    category_id = modal.dataset.selectedCategory;

    if (
        category_id == -1 ||
        !name.length ||
        quantity == -1 ||
        !description.length ||
        !image.files.length
    ) {
        alert("Please Select All The Fields!!!!");
        return;
    }


    let payload = new FormData();
    payload.append("operation", "product.add");
    payload.append("name", name);
    payload.append("quantity", quantity);
    payload.append("description", description);
    payload.append("image", image.files[0]);
    payload.append("category_id", category_id);

    let overlay = document.querySelector("#addProductModal .overlay");
    overlay.classList.remove("hide");

    await fetch("backend.php", {
        method: "POST",
        body: payload
    })
    .then(r => r.json())
    .then(r => {
        if (r.msg == "success") {
            let markup = `<table><tr data-id='${r.id}'>
                            <td>${name}</td>
                            <td>
                                <a href='/product.php?id=${r.id}'>
                                ${name}
                                </a>
                            </td>
                            <td>${quantity}</td>
                            <td>${description}</td>
                            <td>
                                <img width='100px' src='assets/images/${r.image}'>
                            </td>
                            <td>${category_id}</td>
                            <td>
                                <button class='btn btn-info py-0 px-2 text-light m-1'>Edit</button>
                                <button class='btn btn-danger py-0 px-2 text-light m-1' onclick='deleteProduct(this.parentElement.parentElement, ${r.id})'>Delete</button>
                            </td>
                        </tr></table>`;
            let tr = ((new DOMParser()).parseFromString(markup, "text/html")).querySelector("tr");
            console.log(tr);
            document.querySelector("#table-container > table > tbody").appendChild(tr);
            overlay.classList.add("hide");
            $(modal).modal("hide");
        }
    });
    
}

async function loadChildren(label, id) {

    let modal = document.querySelector("#addProductModal");
    modal.dataset.selectedCategory = id;


    // removing previous subcategories
    let on = label.parentElement;
    console.log(on);
    while (1) {
        if (!on.nextElementSibling || !on.nextElementSibling.classList.contains("form-group")) {
            break;
        }
        on.nextElementSibling.remove();
    }

    if (id == -1) {
        return ;
    }

    let payload = new FormData();
    payload.append("operation", "category.getchildren");
    payload.append("id", id);

    let overlay = document.querySelector("#addProductModal .overlay");
    overlay.classList.remove("hide");

    await fetch("backend.php", {
        method: "POST",
        body: payload
    })
    .then(r => r.json())
    .then(r => {
        if (r.msg == "success") {
            console.log(r, r.categories);
            let options = ``;
            if ( r.categories.length ) {
                r.categories.forEach(cat => {
                    options += `<option value='${cat.id}'>${cat.name}</option>`;
                });
                let markup = `
                    <div class="form-group mt-2 category">
                        <label for="category">${label.options[label.selectedIndex].text} : </label>
                        <select onchange="loadChildren(this, this.value)" name="category" id="category" class="form-control" required>
                            <option value='-1'>Choose...</option>
                            ${options}        
                        </select>
                    </div>
                `;
                markup = (new DOMParser()).parseFromString(markup, 'text/html').querySelector(".form-group");
                let modal = document.querySelector("#addProductModal .modal-body .row .col-lg-12");
                console.log(markup);
                modal.appendChild(markup);
            }
            

            overlay.classList.add("hide");
        }
    });


}



function previewImage(files) {
    files.nextElementSibling.src = URL.createObjectURL(files.files[0])
    files.nextElementSibling.classList.remove("hide");
}

function previewImageEditProductModal(files) {
    files.nextElementSibling.src = URL.createObjectURL(files.files[0])
    files.nextElementSibling.classList.remove("hide");
}


function editProduct(tr, pid) {
    let modal = document.querySelector("#editProductModal");

    let editProductModal = document.querySelector("#editProductModal");
    let nameField = editProductModal.querySelector("input[name='name']");
    let quantityField = editProductModal.querySelector("input[name='quantity']");
    let descriptionField = editProductModal.querySelector("textarea[name='description']");
    let imageField = editProductModal.querySelector("input[name='image']");
    let categoryField = editProductModal.querySelector("select[name='category']");

    console.log(tr.querySelector("td:nth-child(2)").innerText);
    nameField.value = tr.querySelector("td:nth-child(2)").innerText;
    quantityField.value = tr.querySelector("td:nth-child(3)").innerText;
    descriptionField.value = tr.querySelector("td:nth-child(4)").innerText;
    modal.dataset.selectedProduct = pid;
    modal.dataset.selectedRow = tr;
    window.selectedRow = tr;
    $(editProductModal).modal("show");
}


async function loadChildrenEditModal(label, id) {

    let modal = document.querySelector("#editProductModal");
    modal.dataset.selectedCategory = id;


    // removing previous subcategories
    let on = label.parentElement;
    // console.log(on);
    while (1) {
        if (!on.nextElementSibling || !on.nextElementSibling.classList.contains("form-group")) {
            break;
        }
        on.nextElementSibling.remove();
    }

    if (id == -1) {
        return;
    }

    let payload = new FormData();
    payload.append("operation", "category.getchildren");
    payload.append("id", id);

    let overlay = document.querySelector("#editProductModal .overlay");
    overlay.classList.remove("hide");

    await fetch("backend.php", {
        method: "POST",
        body: payload
    })
        .then(r => r.json())
        .then(r => {
            if (r.msg == "success") {
                // console.log(r, r.categories);
                let options = ``;
                if (r.categories.length) {
                    r.categories.forEach(cat => {
                        options += `<option value='${cat.id}'>${cat.name}</option>`;
                    });
                    let markup = `
                    <div class="form-group mt-2 category">
                        <label for="category">${label.options[label.selectedIndex].text} : </label>
                        <select onchange="loadChildrenEditModal(this, this.value)" name="category" id="category" class="form-control" required>
                            <option value='-1'>Choose...</option>
                            ${options}        
                        </select>
                    </div>
                `;
                    markup = (new DOMParser()).parseFromString(markup, 'text/html').querySelector(".form-group");
                    let modal = document.querySelector("#editProductModal .modal-body .row .col-lg-12");
                    console.log("---",modal, markup);
                    modal.appendChild(markup);
                }


                overlay.classList.add("hide");
            }
        });


}



async function updateProduct() {
    let modal = document.querySelector("#editProductModal");
    let name = modal.querySelector("input[name='name']").value;
    let quantity = modal.querySelector("input[name='quantity']").value;
    let description = modal.querySelector("textarea[name='description']").value;
    let image = modal.querySelector("input[name='image']");
    let category_id = [...modal.querySelectorAll(".category")];
    let id = modal.dataset.selectedProduct;
    category_id = modal.dataset.selectedCategory;

    if (
        category_id == -1 ||
        !name.length ||
        quantity == -1 ||
        !description.length ||
        !image.files.length
    ) {
        alert("Please Select All The Fields!!!!");
        return;
    }


    let payload = new FormData();
    payload.append("operation", "product.update");
    payload.append("name", name);
    payload.append("id", id);
    payload.append("quantity", quantity);
    payload.append("description", description);
    payload.append("image", image.files[0]);
    payload.append("category_id", category_id);

    let overlay = document.querySelector("#addProductModal .overlay");
    overlay.classList.remove("hide");

    await fetch("backend.php", {
        method: "POST",
        body: payload
    })
    .then(r => r.json())
    .then(r => {
        if (r.msg == "success") {            

            window.selectedRow.querySelector("td:nth-child(2)").innerHTML = `<a href='/product.php?id=${id}'>${name}</a>`;
            window.selectedRow.querySelector("td:nth-child(3)").innerText = quantity;
            window.selectedRow.querySelector("td:nth-child(4)").innerText = description;
            window.selectedRow.querySelector("td:nth-child(5) img").src = `assets/images/${r.image}`;
            window.selectedRow.querySelector("td:nth-child(6)").innerText = category_id;

            $(modal).modal("hide");
        }
    });

}