document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("form-login");

    if (!form) {
        console.error("Formulário de login não encontrado.");
        return;
    }

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const usuario = document.getElementById("usuario").value.trim();
        const senha = document.getElementById("senha").value.trim();
        const csrf_token = document.getElementById("csrf_token").value;

        if (!usuario || !senha) {
            alert("Preencha todos os campos.");
            return;
        }

        const senhaSha256 = await hashSHA256(senha);

        const dados = {
            usuario: usuario,
            senha: senhaSha256,
            csrf_token: csrf_token
        };

        try {
            const resposta = await fetch("/Software_Seguro/application/index.php?action=autenticar", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(dados)
            });

            const resultado = await resposta.json();

            if (resultado.success) {
                alert("Login realizado com sucesso.");
                window.location.href = "/Software_Seguro/application/index.php?pagina=dashboard";
            } else {
                alert(resultado.message || "Falha no login.");
            }
        } catch (error) {
            console.error("Erro:", error);
            alert("Erro interno.");
        }
    });
});

async function hashSHA256(texto) {
    const encoder = new TextEncoder();
    const data = encoder.encode(texto);
    const hashBuffer = await crypto.subtle.digest("SHA-256", data);
    const hashArray = Array.from(new Uint8Array(hashBuffer));
    return hashArray.map(b => b.toString(16).padStart(2, '0')).join('');
}
