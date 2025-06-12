document.addEventListener('DOMContentLoaded', async () => {
    try {
        const resposta = await fetch('/Software_Seguro/utils/verificaSessao.php');
        const resultado = await resposta.json();

        if (!resultado.logado) {
            window.location.href = '/Software_Seguro/application/index.php?pagina=login';
        }
        // Se logado, não faz nada e a página carrega normalmente
    } catch (erro) {
        console.error('Erro ao verificar sessão:', erro);
        window.location.href = '/Software_Seguro/application/index.php?pagina=login';
    }
});
