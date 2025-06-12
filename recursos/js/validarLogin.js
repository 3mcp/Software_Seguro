document.addEventListener("DOMContentLoaded", () => {
    carregarCsrf();

    const form = document.querySelector(".formLogin");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const usuario = document.getElementById("usuario").value.trim();
            const senha = document.getElementById("senha").value.trim();
            const csrf_token = document.getElementById("csrf_token").value;

            if (!usuario || !senha) {
                alert("Preencha todos os campos.");
                return;
            }

            const dados = { usuario, senha, csrf_token };

            try {
                const resposta = await fetch("index.php?action=autenticar", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(dados)
                });

                const resultado = await resposta.json();

                if (resultado.success) {
                    window.location.href = "index.php?pagina=dashboard";
                } else {
                    alert(resultado.message || "Credenciais inválidas.");
                }
            } catch (erro) {
                console.error("Erro ao enviar login:", erro);
            }
        });
    }
});

async function carregarCsrf() {
    try {
        const resposta = await fetch("/Software_Seguro/utils/csrf_token.php");
        const dados = await resposta.json();
        document.getElementById("csrf_token").value = dados.token;
    } catch (erro) {
        console.error("Erro ao obter o token CSRF:", erro);
    }
}
