import RoadmapManager from './RoadmapManager.js';
import RoadmapRenderer from './RoadmapRenderer.js';

export default class Roadmap {
    constructor(containerId, roadmapData, sidebarSelector, svgId) {
        this.manager = new RoadmapManager(roadmapData);
        this.renderer = new RoadmapRenderer(containerId, this.manager, sidebarSelector, svgId);
    }

    initialize() {
        this.renderer.renderSteps();
        this.renderer.updateSidebar();
    }
}