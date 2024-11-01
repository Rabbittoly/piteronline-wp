document.addEventListener("DOMContentLoaded", function() {
    const cyrillicToLatin = (str) => {
        str = str.replace(/nbsp/g, ' '); // Заменяем 'nbsp' на пробел
        str = str.replace(/8212/g, '');  // Убираем '8212'
        const converter = {
            'а': 'a',   'б': 'b',   'в': 'v',
            'г': 'g',   'д': 'd',   'е': 'e',
            'ё': 'e',   'ж': 'zh',  'з': 'z',
            'и': 'i',   'й': 'y',   'к': 'k',
            'л': 'l',   'м': 'm',   'н': 'n',
            'о': 'o',   'п': 'p',   'р': 'r',
            'с': 's',   'т': 't',   'у': 'u',
            'ф': 'f',   'х': 'h',   'ц': 'c',
            'ч': 'ch',  'ш': 'sh',  'щ': 'sch',
            'ь': '-',    'ы': 'y',   'ъ': '-',
            'э': 'e',   'ю': 'yu',  'я': 'ya',

            'А': 'A',   'Б': 'B',   'В': 'V',
            'Г': 'G',   'Д': 'D',   'Е': 'E',
            'Ё': 'E',   'Ж': 'Zh',  'З': 'Z',
            'И': 'I',   'Й': 'Y',   'К': 'K',
            'Л': 'L',   'М': 'M',   'Н': 'N',
            'О': 'O',   'П': 'P',   'Р': 'R',
            'С': 'S',   'Т': 'T',   'У': 'U',
            'Ф': 'F',   'Х': 'H',   'Ц': 'C',
            'Ч': 'Ch',  'Ш': 'Sh',  'Щ': 'Sch',
            'Ь': '-',    'Ы': 'Y',   'Ъ': '-',
            'Э': 'E',   'Ю': 'Yu',  'Я': 'Ya'
        };
        return [...str].map(char => converter[char] || char).join('');
    };

    for (const element of document.querySelectorAll('[id]')) {
        const id = element.id; 
        const latinId = cyrillicToLatin(id);
        if (id !== latinId) {
            element.id = latinId;
        }
    }

    for (const a of document.querySelectorAll('a[href^="#"]')) {
        const fragment = decodeURIComponent(a.getAttribute("href").substring(1)); // Декодируем URL
        const latinFragment = cyrillicToLatin(fragment);
        if (fragment !== latinFragment) {
            a.setAttribute("href", "#" + encodeURIComponent(latinFragment)); // Кодируем URL обратно
        }
    }
});