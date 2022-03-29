import { createGlobalStyle } from "styled-components";

export default class extends elementorModules.frontend.handlers.Base {
	__construct( ...args ) {
		super.__construct( ...args );

		this.toggle = elementorFrontend.debounce( this.toggle, 200 );
	}

	onInit() {
		super.onInit();
		console.log(first)
		
		
		
	}

	onElementChange( propertyName ) {	
		
	}
}
