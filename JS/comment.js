function validateForm() {
    var name = document.getElementById("name").value;
    var rating = document.getElementById("rating").value;
    var comment = document.getElementById("comment").value;
    
    if (name == "" || rating == "" || comment == "") {
      alert("All fields must be filled out");
      return false;
    }
    
    return true;
  }
  