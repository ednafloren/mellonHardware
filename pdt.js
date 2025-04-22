
    let productIdToDelete;

    function updateRecord(id) {
        // Redirect to update page with the ID parameter
        window.location.href = 'pdtupdate.php?id=' + id;
    }

    function showModal(id) {
        productIdToDelete = id;
        document.getElementById('deleteModal').style.display = 'block';
    }

    function hideModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        window.location.href = 'delete_product.php?id=' + productIdToDelete;
    });

  
