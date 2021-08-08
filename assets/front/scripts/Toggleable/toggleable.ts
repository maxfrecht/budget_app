export function toggleForm() {
        let btnToggleElements = document.querySelectorAll('.toggle-form');
        btnToggleElements?.forEach(btn => {
            let toggleAble: HTMLElement = btn.parentElement.querySelector('.toggleable')
            btn.addEventListener('click', (e) => {
                if(!toggleAble.style.maxHeight || toggleAble.style.maxHeight === '0px') {
                    btn.classList.add('open');
                    toggleAble.style.maxHeight = toggleAble.scrollHeight + 'px';
                    toggleAble.style.opacity = '1'
                } else {
                    btn.classList.remove('open');
                    toggleAble.style.maxHeight = '0px';
                    toggleAble.style.opacity = '0'
                }
            })
        })

}