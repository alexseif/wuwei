import RoadmapManager from './RoadmapManager.js';

// Define constants for step dimensions
const STEP_WIDTH = 200; // Width of each step in pixels
const STEP_HEIGHT = 56; // Height of each step in pixels
const STEP_SPACING = 20; // Vertical spacing between steps
const MIN_POSITION_REM = 1; // Minimum position in rem

export default class RoadmapRenderer {
    constructor(containerId, roadmapManager, sidebarSelector = '.sidebar.panel:last-child', svgId = 'roadmap-lines') {
        this.container = document.getElementById(containerId);
        this.manager = roadmapManager;
        this.sidebarSelector = sidebarSelector;
        this.svgId = svgId;

        // Convert 1rem to pixels
        this.minPositionPx = parseFloat(getComputedStyle(document.documentElement).fontSize) * MIN_POSITION_REM;
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
        sidebar.appendChild(this.createButton('Reset', 'btn btn-secondary', () => this.resetRoadmap()));
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
     * Resets the roadmap to its last saved state.
     */
    resetRoadmap() {
        this.manager.resetStepsToOriginalPositions();
        this.reRenderRoadmap();
    }

    /**
     * Renders all steps in the roadmap.
     */
    renderSteps() {
        let previousStep = null;

        this.manager.data.steps.forEach((step, index) => {
            const stepElement = this.createStepElement(step, index, previousStep);
            this.container.appendChild(stepElement);

            if (previousStep) {
                this.drawLineBetweenSteps(previousStep, step);
            }

            previousStep = step;
        });
    }

    createStepElement(step, index, prevStep) {
        const stepDiv = document.createElement('div');
        stepDiv.className = `box step ${step.start ? 'start' : ''} ${step.end ? 'end' : ''}`;

        if (prevStep) stepDiv.classList.add('has-before');
        if (index < this.manager.data.steps.length - 1) stepDiv.classList.add('has-after');

        const top = step.top ?? (prevStep ? prevStep.top + STEP_HEIGHT + STEP_SPACING : 0);
        const left = step.left ?? (prevStep ? prevStep.left : 0);


        step.originalTitle = step.title;
        step.originalTop = step.originalTop ?? top;
        step.originalLeft = step.originalLeft ?? left;

        stepDiv.style.top = `${top}px`;
        stepDiv.style.left = `${left}px`;
        stepDiv.style.width = `${STEP_WIDTH}px`;
        stepDiv.style.height = `${STEP_HEIGHT}px`;
        stepDiv.textContent = step.title;

        step.top = top;
        step.left = left;

        this.makeDraggable(stepDiv, step);
        stepDiv.addEventListener('dblclick', () => this.editStepTitle(step, stepDiv));

        return stepDiv;
    }

    makeDraggable(element, step) {
        let isDragging = false;
        let offsetX, offsetY;

        const onMouseMove = (event) => {
            if (isDragging) {
                let newLeft = event.clientX - offsetX;
                let newTop = event.clientY - offsetY;

                // Ensure top and left are not less than the minimum position
                newLeft = Math.max(newLeft, this.minPositionPx);
                newTop = Math.max(newTop, this.minPositionPx);

                element.style.left = `${newLeft}px`;
                element.style.top = `${newTop}px`;

                step.left = newLeft;
                step.top = newTop;

                this.updateLines();
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
     * Allows the user to edit the title of a step.
     */
    editStepTitle(step, stepDiv) {
        const input = document.createElement('input');
        input.type = 'text';
        input.value = step.title;
        input.className = 'step-title-input';

        stepDiv.textContent = '';
        stepDiv.appendChild(input);
        input.focus();

        const saveTitle = () => {
            step.title = input.value.trim() || step.title;
            stepDiv.textContent = step.title;
            input.remove();
            this.updateSidebar();
        };

        input.addEventListener('blur', saveTitle);
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') saveTitle();
        });
    }

    /**
     * Draws a line between two steps in the roadmap.
     */
    drawLineBetweenSteps(step1, step2) {
        const svg = document.getElementById(this.svgId);
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');

        line.setAttribute('x1', step1.left + STEP_WIDTH / 2);
        line.setAttribute('y1', step1.top + STEP_HEIGHT / 2);
        line.setAttribute('x2', step2.left + STEP_WIDTH / 2);
        line.setAttribute('y2', step2.top + STEP_HEIGHT / 2);
        line.setAttribute('stroke', '#E040FB');
        line.setAttribute('stroke-width', '2');

        svg.appendChild(line);
    }

    /**
     * Updates all lines connecting the steps in the roadmap.
     */
    updateLines() {
        const svg = document.getElementById(this.svgId);
        svg.innerHTML = '';

        const steps = this.manager.data.steps;
        for (let i = 0; i < steps.length - 1; i++) {
            this.drawLineBetweenSteps(steps[i], steps[i + 1]);
        }
    }

    /**
     * Saves the roadmap data to the server.
     */
    saveRoadmapToServer(yamlContent) {
        fetch('/roadmap/save', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ yaml: yamlContent }),
        })
            .then((response) => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                this.manager.data.steps.forEach((step) => {
                    step.originalTitle = step.title;
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

        const totalHeight = this.manager.data.steps.length * (STEP_HEIGHT + STEP_SPACING) - STEP_SPACING;
        let currentTop = (roadmapHeight - totalHeight) / 2;

        this.manager.data.steps.forEach((step) => {
            step.left = (roadmapWidth - STEP_WIDTH) / 2;
            step.top = currentTop;
            currentTop += STEP_HEIGHT + STEP_SPACING;
        });

        this.reRenderRoadmap();
    }

    /**
     * Clears and re-renders the roadmap.
     */
    reRenderRoadmap() {
        this.container.innerHTML = '';
        const svg = document.getElementById(this.svgId);
        svg.innerHTML = '';

        this.renderSteps();
        this.updateSidebar();
    }
}