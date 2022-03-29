import Handler from './handler';

export default class extends elementorModules.Module {
	constructor() {
		super();

		elementorFrontend.elementsHandler.attachHandler( 'global', Handler, null );
	}
}
