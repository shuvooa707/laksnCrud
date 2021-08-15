function editProduct(pid) {
    let editProductModal = document.querySelector("#editProductModal");
    let nameField = document.querySelector("input[name='name']");
    let quantityField = document.querySelector("input[name='quantity']");
    let descriptionField = document.querySelector("textarea[name='description']");
    let imageField = document.querySelector("input[name='image']");
    let categoryField = document.querySelector("select[name='category']");

    $(editProductModal).modal("show");
}




async function deleteProduct(pid) {
    let payload = new FormData();
    payload.append("operation", "product.delete");
    payload.append("id", pid);

    console.log(pid);

    // let overlay = document.querySelector("#table-container .overlay");
    // overlay.classList.remove("hide");

    await fetch("backend.php", {
        method: "POST",
        body: payload
    })
        .then(r => r.json())
        .then(r => {
            if (r.msg == "success") {
                window.location = "/";
            }
        });
}