import udomdiff from 'udomdiff';
import { scriptsSetup } from "./scripts-setup";

export function customUrlBehavior () {
    window.onload = function () {
        updateLinks();

        async function updateDom(path) {
            // console.log("New content from: ", path);
            const res = await fetch(path);
            const data = await res.text();

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
                        history.pushState({ route: destination }, destination, destination);
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


