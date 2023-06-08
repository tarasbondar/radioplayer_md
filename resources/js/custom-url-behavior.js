import udomdiff from 'udomdiff';
import { scriptsSetup } from "./scripts-setup";

export function customUrlBehavior () {
    window.onload = function () {
        updateLinks();

        async function updateDom(path) {
            // console.log("New content from: ", path);
            const res = await fetch(path);
            let data = null;
            if (res.redirected) {
                const redirectedUrl = res.url;
                const redirectedRes = await fetch(redirectedUrl);
                path = redirectedUrl;
                data = await redirectedRes.text();
            } else {
                data = await res.text();
            }
            //const data = await res.text();

            const get = (o) => o;
            const parent = document.querySelector("#appContainer");
            const currentNodes = document.querySelector("#appContainer").childNodes;

            document.querySelector("body").classList.remove("menu-open");

            const dataNodes = new DOMParser()
                .parseFromString(data, "text/html")
                .querySelector("#appContainer").childNodes;

            udomdiff(
                parent, // where changes happen
                [...currentNodes], // Array of current items/nodes
                [...dataNodes], // Array of future items/nodes (returned)
                get // a callback to retrieve the node
            );
            history.pushState({ route: path }, path, path);
            //console.log(path);
            updateLinks();
            scriptsSetup();
            window.scrollTo(0, 0);
        }

        function updateLinks() {
            document.querySelectorAll("a").forEach((link) => {
                if (link.host === window.location.host) {
                    link.addEventListener("click", async (e) => {
                        const destination = link.getAttribute("href");
                        e.preventDefault();

                        await updateDom(destination);
                    });
                }
            });
        }

        window.onpopstate = function () {
            updateDom(window.location.pathname);
        };
    };
}


