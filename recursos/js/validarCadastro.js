document.addEventListener("DOMContentLoaded", () => {
    carregarCsrf();

    const form = document.querySelector(".formCadastro");
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();

            const usuario = document.getElementById("usuario").value.trim();
            const senha = document.getElementById("senha").value.trim();
            const confirmarSenha = document.getElementById("confirmar_senha").value.trim();
            const csrf_token = document.getElementById("csrf_token").value;

            if (!usuario || !senha || !confirmarSenha) {
                alert("Preencha todos os campos.");
                return;
            }

            if (senha !== confirmarSenha) {
                alert("As senhas não coincidem.");
                return;
            }

            if (senha.length < 12) {
                alert("A senha deve conter pelo menos 12 caracteres.");
                return;
            }

            const dados = { usuario, senha, csrf_token };

            try {
                const resposta = await fetch("index.php?action=cadastrarUsuario", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(dados)
                });

                const resultado = await resposta.json();

                if (resultado.success) {
                    alert("Cadastro realizado com sucesso!");
                    window.location.href = "index.php?pagina=login";
                } else {
                    alert(resultado.message || "Erro no cadastro.");
                }
            } catch (erro) {
                console.error("Erro ao enviar cadastro:", erro);
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
