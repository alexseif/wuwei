// roadmap.js
export default class Roadmap {

    constructor(containerId, roadmapData) {
        this.container = document.getElementById(containerId);
        this.data = roadmapData;
    }

    initialize() {
        this.renderSteps();
        this.updateSidebar(); // Initialize the sidebar with the initial data

        const roadmapContainer = this.container;

        // Sync SVG position with scroll
        roadmapContainer.addEventListener('scroll', () => {
            const svg = document.getElementById('roadmap-lines');
            svg.style.transform = `translate(${-roadmapContainer.scrollLeft}px, ${-roadmapContainer.scrollTop}px)`;
        });
    }


    createStepElement(step, index, prevStep) {
        const stepDiv = document.createElement("div");
        stepDiv.className = `box step 
            ${step.start ? 'start' : ''} 
            ${step.end ? 'end' : ''}`;

        if (prevStep) stepDiv.classList.add("has-before");
        if (index < this.data.steps.length - 1) stepDiv.classList.add("has-after");

        const top = step.top ?? (prevStep ? prevStep.top + 150 : 0); // Vertical offset
        const left = step.left ?? (prevStep ? prevStep.left : 0);    // Horizontal position
        step.originalTop = step.originalTop ?? step.top; // Store original top
        step.originalLeft = step.originalLeft ?? step.left; // Store original left

        stepDiv.style.top = `${top}px`;
        stepDiv.style.left = `${left}px`;

        stepDiv.style.width = '200px'; // Fixed width
        stepDiv.style.height = '56px'; // Fixed height

        stepDiv.textContent = step.title;

        // Attach position back to the step data for reference
        step.top = top;
        step.left = left;
        step.width = 200; // Store width for connections
        step.height = 56; // Store height for connections

        this.makeDraggable(stepDiv, step);

        return stepDiv;
    }

    renderSteps() {
        let previousStep = null;

        this.data.steps.forEach((step, index) => {
            const stepElement = this.createStepElement(step, index, previousStep);

            // Append to the roadmap container
            this.container.appendChild(stepElement);

            // Add connection line to the previous step
            if (previousStep) {
                this.drawLineBetweenSteps(previousStep, step);
            }

            // Update the previous step reference
            previousStep = step;
        });
    }

    makeDraggable(element, step) {
        let isDragging = false;
        let offsetX, offsetY;

        element.addEventListener('mousedown', (event) => {
            isDragging = true;
            offsetX = event.clientX - element.offsetLeft;
            offsetY = event.clientY - element.offsetTop;
            element.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', (event) => {
            if (isDragging) {
                const newLeft = event.clientX - offsetX;
                const newTop = event.clientY - offsetY;

                // Update the element's position
                element.style.left = `${newLeft}px`;
                element.style.top = `${newTop}px`;

                // Update the step's position in the data
                step.left = newLeft;
                step.top = newTop;

                // Update lines dynamically without re-rendering
                this.updateLines();
            }
        });

        document.addEventListener('mouseup', () => {
            if (isDragging) {
                isDragging = false;
                element.style.cursor = 'grab';
                this.updateSidebar(); // Update the sidebar when dragging stops
            }
        });

        // Set the initial cursor style
        element.style.cursor = 'grab';
    }



    updateSidebar() {
        const sidebar = document.querySelector('.sidebar.panel:last-child');
        sidebar.innerHTML = ''; // Clear the sidebar content

        // Generate YAML for the steps
        let yamlContent = `roadmap:\n  title: "${this.data.title}"\n  steps:\n`;

        this.data.steps.forEach((step) => {
            yamlContent += `    - title: "${step.title}"\n`;
            if (step.start) yamlContent += `      start: true\n`;
            if (step.end) yamlContent += `      end: true\n`;
            yamlContent += `      top: ${Math.round(step.top)}\n`;
            yamlContent += `      left: ${Math.round(step.left)}\n`;
        });

        // Display YAML content
        const yamlElement = document.createElement('pre');
        yamlElement.textContent = yamlContent;
        sidebar.appendChild(yamlElement);

        // Add "Save" button
        const saveButton = document.createElement('button');
        saveButton.textContent = 'Save';
        saveButton.className = 'btn btn-primary';
        saveButton.addEventListener('click', () => {
            this.saveRoadmapToServer(yamlContent); // Call save function
        });

        sidebar.appendChild(saveButton);

        // Add "Reset" button
        const resetButton = document.createElement('button');
        resetButton.textContent = 'Reset';
        resetButton.className = 'btn btn-secondary';
        resetButton.addEventListener('click', () => {
            this.resetRoadmap(); // Call reset function
        });

        sidebar.appendChild(resetButton);

        // Add "Center and Stack" button
        const stackButton = document.createElement('button');
        stackButton.textContent = 'Center and Stack';
        stackButton.className = 'btn btn-success';
        stackButton.addEventListener('click', () => {
            this.centerAndStackSteps(); // Call center and stack function
        });
        sidebar.appendChild(stackButton);
    }


    saveRoadmapToServer(yamlContent) {
        fetch('/roadmap/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ yaml: yamlContent }),
        })
            .then((response) => {
                if (response.ok) {
                    alert('Roadmap saved successfully!');
                } else {
                    alert('Failed to save the roadmap. Please try again.');
                }
            })
            .catch((error) => {
                console.error('Error saving roadmap:', error);
                alert('An error occurred while saving the roadmap.');
            });
    }

    resetRoadmap() {
        // Clear the container and SVG
        this.container.innerHTML = '';
        const svg = document.getElementById('roadmap-lines');
        svg.innerHTML = ''; // Clear all lines

        // Reset steps to their original positions
        this.data.steps.forEach((step) => {
            step.top = step.originalTop;
            step.left = step.originalLeft;
        });

        // Re-render steps and sidebar
        this.renderSteps();
        this.updateSidebar();
    }



    drawLineBetweenSteps(step1, step2) {
        const svg = document.getElementById('roadmap-lines');
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');

        // Set line attributes
        line.setAttribute('x1', step1.left + step1.width / 2); // Center of the first step
        line.setAttribute('y1', step1.top + step1.height / 2); // Center of the first step
        line.setAttribute('x2', step2.left + step2.width / 2); // Center of the second step
        line.setAttribute('y2', step2.top + step2.height / 2); // Center of the second step
        line.setAttribute('stroke', '#FFD700'); // Line color
        line.setAttribute('stroke-width', '2'); // Line width

        svg.appendChild(line);
    }

    updateLines() {
        const svg = document.getElementById('roadmap-lines');
        svg.innerHTML = ''; // Clear current lines

        for (let i = 1; i < this.data.steps.length; i++) {
            const step1 = this.data.steps[i - 1];
            const step2 = this.data.steps[i];
            this.drawLineBetweenSteps(step1, step2); // Redraw lines only
        }
    }

    centerAndStackSteps() {
        const roadmapHeight = this.container.offsetHeight; // Height of the roadmap container
        const roadmapWidth = this.container.offsetWidth; // Width of the roadmap container
        const stepHeight = 56; // Fixed height of each step
        const spacing = 20; // Spacing between steps
        const totalHeight = this.data.steps.length * (stepHeight + spacing) - spacing;

        // Start Y position to vertically center all steps
        let currentTop = (roadmapHeight - totalHeight) / 2;

        this.data.steps.forEach((step) => {
            step.left = (roadmapWidth - step.width) / 2; // Horizontally center the step
            step.top = currentTop; // Set the top position
            currentTop += stepHeight + spacing; // Increment for the next step
        });

        // Clear and re-render the roadmap and lines
        this.container.innerHTML = '';
        const svg = document.getElementById('roadmap-lines');
        svg.innerHTML = '';
        this.renderSteps(); // Redraw steps
        this.updateLines(); // Redraw lines
        this.updateSidebar(); // Update the sidebar with new positions
    }

}
