export const loadScript = (url) => {
    let script = document.createElement('script');
    script.setAttribute('src', url);
    document.head.appendChild(script);
};
