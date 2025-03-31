import RoadmapManager from './RoadmapManager.js';

// Define constants for step dimensions
const STEP_WIDTH = 200; // Width of each step in pixels
const STEP_HEIGHT = 56; // Height of each step in pixels

export default class RoadmapRenderer {
    constructor(containerId, roadmapManager, sidebarSelector = '.sidebar.panel:last-child', svgId = 'roadmap-lines') {
        this.container = document.getElementById(containerId);
        this.manager = roadmapManager;
        this.sidebarSelector = sidebarSelector;
        this.svgId = svgId;
    }

    /**
     * Updates the sidebar with the current roadmap data.
     */
    updateSidebar() {
        const sidebar = document.querySelector(this.sidebarSelector);
        this.clearSidebar(sidebar);

        const yamlContent = this.manager.generateYamlContent();
        this.addYamlToSidebar(sidebar, yamlContent);
        this.addButtonsToSidebar(sidebar, yamlContent);
    }

    clearSidebar(sidebar) {
        sidebar.innerHTML = ''; // Clear the sidebar content
    }

    addYamlToSidebar(sidebar, yamlContent) {
        const yamlElement = document.createElement('pre');
        yamlElement.textContent = yamlContent;
        sidebar.appendChild(yamlElement);
    }

    addButtonsToSidebar(sidebar, yamlContent) {
        sidebar.appendChild(this.createButton('Save', 'btn btn-primary', () => this.saveRoadmapToServer(yamlContent)));
        sidebar.appendChild(this.createButton('Reset', 'btn btn-secondary', () => this.resetRoadmap())); // Correct reference
        sidebar.appendChild(this.createButton('Center and Stack', 'btn btn-success', () => this.centerAndStackSteps()));
    }

    createButton(text, className, onClickHandler) {
        const button = document.createElement('button');
        button.textContent = text;
        button.className = className;
        button.addEventListener('click', onClickHandler);
        return button;
    }

    /**
     * Resets the roadmap to its initial state.
     */
    resetRoadmap() {
        // Reset step positions using the manager
        this.manager.resetStepsToOriginalPositions();

        // Clear and re-render the roadmap
        this.container.innerHTML = '';
        const svg = document.getElementById(this.svgId);
        svg.innerHTML = '';

        this.renderSteps();
        this.updateSidebar();
    }

    /**
     * Renders all steps in the roadmap.
     */
    renderSteps() {
        let previousStep = null;

        this.manager.data.steps.forEach((step, index) => {
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

    createStepElement(step, index, prevStep) {
        const stepDiv = document.createElement("div");
        stepDiv.className = `box step 
            ${step.start ? 'start' : ''} 
            ${step.end ? 'end' : ''}`;

        if (prevStep) stepDiv.classList.add("has-before");
        if (index < this.manager.data.steps.length - 1) stepDiv.classList.add("has-after");

        const top = step.top ?? (prevStep ? prevStep.top + 150 : 0); // Vertical offset
        const left = step.left ?? (prevStep ? prevStep.left : 0);    // Horizontal position
        step.originalTop = step.originalTop ?? step.top; // Store original top
        step.originalLeft = step.originalLeft ?? step.left; // Store original left

        stepDiv.style.top = `${top}px`;
        stepDiv.style.left = `${left}px`;

        stepDiv.style.width = `${STEP_WIDTH}px`;
        stepDiv.style.height = `${STEP_HEIGHT}px`;

        stepDiv.textContent = step.title;

        // Attach position back to the step data for reference
        step.top = top;
        step.left = left;
        step.width = STEP_WIDTH; // Store width for connections
        step.height = STEP_HEIGHT; // Store height for connections

        // Make the step draggable
        this.makeDraggable(stepDiv, step);

        // Add double-click event listener for editing the title
        stepDiv.addEventListener('dblclick', () => this.editStepTitle(step, stepDiv));

        return stepDiv;
    }

    makeDraggable(element, step) {
        let isDragging = false;
        let offsetX, offsetY;

        // Convert 1rem to pixels (default is 16px)
        const remToPx = parseFloat(getComputedStyle(document.documentElement).fontSize);

        const onMouseMove = (event) => {
            if (isDragging) {
                let newLeft = event.clientX - offsetX;
                let newTop = event.clientY - offsetY;

                // Ensure top and left are not less than 1rem
                newLeft = Math.max(newLeft, remToPx);
                newTop = Math.max(newTop, remToPx);

                element.style.left = `${newLeft}px`;
                element.style.top = `${newTop}px`;

                step.left = newLeft;
                step.top = newTop;

                this.updateLines(); // Update lines when the step is moved
            }
        };

        const onMouseUp = () => {
            if (isDragging) {
                isDragging = false;
                element.style.cursor = 'grab';
                this.updateSidebar();
            }
        };

        element.addEventListener('mousedown', (event) => {
            isDragging = true;
            offsetX = event.clientX - element.offsetLeft;
            offsetY = event.clientY - element.offsetTop;
            element.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', onMouseMove);
        document.addEventListener('mouseup', onMouseUp);

        element.style.cursor = 'grab';
    }

    /**
     * Draws a line between two steps in the roadmap.
     * @param {Object} step1 - The first step.
     * @param {Object} step2 - The second step.
     */
    drawLineBetweenSteps(step1, step2) {
        const svg = document.getElementById(this.svgId);

        // Create a new line element
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', step1.left + STEP_WIDTH / 2);
        line.setAttribute('y1', step1.top + STEP_HEIGHT / 2);
        line.setAttribute('x2', step2.left + STEP_WIDTH / 2);
        line.setAttribute('y2', step2.top + STEP_HEIGHT / 2);
        line.setAttribute('stroke', '#E040FB');
        line.setAttribute('stroke-width', '2');

        // Append the line to the SVG container
        svg.appendChild(line);
    }

    /**
     * Updates all lines connecting the steps in the roadmap.
     */
    updateLines() {
        const svg = document.getElementById(this.svgId);

        // Clear existing lines
        svg.innerHTML = '';

        // Redraw lines between steps
        const steps = this.manager.data.steps;
        for (let i = 0; i < steps.length - 1; i++) {
            this.drawLineBetweenSteps(steps[i], steps[i + 1]);
        }
    }

    /**
 * Saves the roadmap data to the server.
 * @param {string} yamlContent - The YAML content to save.
 */
    saveRoadmapToServer(yamlContent) {
        fetch('/roadmap/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ yaml: yamlContent }),
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                // Update the original positions to the current positions
                this.manager.data.steps.forEach((step) => {
                    step.originalTop = step.top;
                    step.originalLeft = step.left;
                });

                alert('Roadmap saved successfully!');
            })
            .catch((error) => {
                console.error('Error saving roadmap:', error);
                alert('An error occurred while saving the roadmap.');
            });
    }

    /**
 * Centers and stacks the steps in the roadmap.
 */
    centerAndStackSteps() {
        const roadmapWidth = this.container.offsetWidth;
        const roadmapHeight = this.container.offsetHeight;

        // Calculate vertical spacing
        const totalHeight = this.manager.data.steps.length * (STEP_HEIGHT + 20) - 20;
        let currentTop = (roadmapHeight - totalHeight) / 2;

        this.manager.data.steps.forEach((step) => {
            step.left = (roadmapWidth - STEP_WIDTH) / 2; // Center horizontally
            step.top = currentTop; // Stack vertically
            currentTop += STEP_HEIGHT + 20; // Add spacing
        });

        // Clear and re-render the roadmap
        this.container.innerHTML = '';
        const svg = document.getElementById(this.svgId);
        svg.innerHTML = '';

        this.renderSteps();
        this.updateSidebar();
    }

    /**
 * Allows the user to edit the title of a step.
 * @param {Object} step - The step object.
 * @param {HTMLElement} stepDiv - The step's DOM element.
 */
    editStepTitle(step, stepDiv) {
        // Create an input field
        const input = document.createElement('input');
        input.type = 'text';
        input.value = step.title;
        input.className = 'step-title-input';

        // Replace the step's title with the input field
        stepDiv.textContent = '';
        stepDiv.appendChild(input);

        // Focus the input field
        input.focus();

        // Save the new title when the user presses "Enter" or loses focus
        const saveTitle = () => {
            step.title = input.value.trim() || step.title; // Keep the old title if input is empty
            stepDiv.textContent = step.title; // Update the step's title in the DOM
            input.remove(); // Remove the input field
            this.updateSidebar(); // Update the sidebar to reflect the new title
        };

        input.addEventListener('blur', saveTitle); // Save on blur
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                saveTitle(); // Save on Enter key
            }
        });
    }
}