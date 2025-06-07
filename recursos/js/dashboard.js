document.addEventListener("DOMContentLoaded", function () {
      fetch("../application/Controllers/VerificaAdmin.php")
        .then(res => res.json())
        .then(data => {
          if (data.admin) {
            const grid = document.querySelector(".grid-opcoes");
            const card = document.createElement("a");
            card.href = "?pagina=cadastrar";
            card.className = "card-opcao";
            card.innerHTML = `
              <div class="icone">👥</div>
              <div class="texto">
                <h3>Cadastrar Usuário</h3>
                <p>Cadastrar Novo Usuário (somente para administradores).</p>
              </div>`;
            grid.appendChild(card);
          }
        })
        .catch(err => console.error("Erro ao verificar permissão:", err));
    });