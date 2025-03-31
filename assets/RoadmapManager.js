export default class RoadmapManager {
    constructor(roadmapData) {
        this.data = roadmapData;
    }

    /**
     * Generates YAML content for the roadmap.
     * @returns {string} The YAML representation of the roadmap.
     */
    generateYamlContent() {
        const formatStep = (step) => {
            const properties = [
                `    - title: "${step.title}"`,
                step.start ? `      start: true` : '',
                step.end ? `      end: true` : '',
                `      top: ${Math.round(step.top)}`,
                `      left: ${Math.round(step.left)}`
            ].filter(Boolean); // Remove empty strings
            return properties.join('\n');
        };

        return `roadmap:\n  title: "${this.data.title}"\n  steps:\n` +
            this.data.steps.map(formatStep).join('\n');
    }

    /**
     * Resets steps to their original positions.
     */
    resetStepsToOriginalPositions() {
        this.data.steps.forEach((step) => {
            step.top = step.originalTop;
            step.left = step.originalLeft;
        });
    }
}