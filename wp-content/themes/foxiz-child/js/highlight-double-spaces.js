document.addEventListener('DOMContentLoaded', function() {
    // Наблюдатель за изменениями в DOM
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            if (mutation.addedNodes.length) {
                mutation.addedNodes.forEach(node => {
                    if (node.nodeType === Node.ELEMENT_NODE) {
                        // Проверка на наличие текстового содержимого
                        if (node.textContent.includes('  ')) {
                            // Замена двойных пробелов на специальный HTML с классом
                            const updatedHTML = node.innerHTML.replace(/ {2}/g, '<span class="double-space">  </span>');
                            node.innerHTML = updatedHTML;
                        }
                    }
                });
            }
        });
    });

    // Начать наблюдение
    const editor = document.querySelector('.editor-styles-wrapper');
    if (editor) {
        observer.observe(editor, { childList: true, subtree: true });
    }
});
