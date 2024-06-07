// Gestion des likes
document.querySelectorAll(".like").forEach((button) => {
  button.addEventListener("click", () => {
    const produitId = button.getAttribute("data-id");
    button.classList.toggle("active");
    fetch("/actions/like.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ produit_id: produitId }),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          button.textContent = button.classList.contains("active")
            ? "❤️"
            : "🤍";
        }
      });
  });
});

// Gestion des commentaires
document.querySelectorAll(".comment").forEach((button) => {
  button.addEventListener("click", () => {
    const produitId = button.getAttribute("data-id");
    const commentaire = prompt("Entrez votre commentaire:");
    if (commentaire) {
      fetch("/actions/comment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          produit_id: produitId,
          commentaire: commentaire,
        }),
      })
        .then((response) => response.json())
        .then((response) => {
          if (response.success) {
            const commentairesDiv = button
              .closest(".produit")
              .querySelector(".commentaires");
            commentairesDiv.innerHTML += `<p><strong>Vous:</strong> ${commentaire}</p>`;
          } else {
            alert("Une erreur est survenue lors de l'ajout du commentaire.");
          }
        });
    }
  });
});

// Gestion de l'ajout au panier
document.querySelectorAll(".add-to-cart").forEach((button) => {
  button.addEventListener("click", () => {
    const produitId = button.getAttribute("data-id");
    fetch("/actions/add_to_cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ produit_id: produitId, quantity: 1 }),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          alert("Produit ajouté au panier avec succès.");
          location.reload(); // Recharger la page pour mettre à jour le panier
        } else {
          alert("Une erreur est survenue lors de l'ajout au panier.");
        }
      });
  });
});

// Gestion de la mise à jour de la quantité dans le panier
document.querySelectorAll(".cart-quantity").forEach((input) => {
  input.addEventListener("change", () => {
    const cartItemId = input.getAttribute("data-id");
    const quantity = input.value;
    fetch("/actions/update_cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ cart_item_id: cartItemId, quantity: quantity }),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          alert("Quantité mise à jour avec succès.");
          location.reload(); // Recharger la page pour mettre à jour le panier
        } else {
          alert(
            "Une erreur est survenue lors de la mise à jour de la quantité."
          );
        }
      });
  });
});

// Gestion de la suppression du panier
document.querySelectorAll(".remove-from-cart").forEach((button) => {
  button.addEventListener("click", () => {
    const cartItemId = button.getAttribute("data-id");
    fetch("/actions/remove_from_cart.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ cart_item_id: cartItemId }),
    })
      .then((response) => response.json())
      .then((response) => {
        if (response.success) {
          alert("Produit supprimé du panier avec succès.");
          location.reload(); // Recharger la page pour mettre à jour le panier
        } else {
          alert(
            "Une erreur est survenue lors de la suppression du produit du panier."
          );
        }
      });
  });
});

// Validation du panier
document.getElementById("validate-cart").addEventListener("click", () => {
  fetch("/actions/validate_cart.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
  })
    .then((response) => response.json())
    .then((response) => {
      if (response.success) {
        alert("Commande validée avec succès.");
        location.reload(); // Recharger la page pour réinitialiser le panier
      } else {
        alert("Une erreur est survenue lors de la validation de la commande.");
      }
    });
});
