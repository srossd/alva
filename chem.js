function Expression() {
    this.equals = function(expr) {};
	this.toHTML = function() {};
}

Reaction.prototype = new Expression();
Reaction.prototype.constructor = Reaction;

function Reaction(s1,arrow,s2) {
	this.s1 = s1;
	this.arrow = arrow;
	this.s2 = s2;
	
    this.equals = function(expr) {
		if(expr===undefined) 
			return false;
		
		return this.s1.equals(expr.s1) && this.arrow == expr.arrow && this.s2.equals(expr.s2);
	};
	this.toHTML = function() {
		return s1.toHTML() + " " + arrow + " " + s2.toHTML();
	};
}

Side.prototype = new Expression();
Side.prototype.constructor = Side;

function Side(molecule,rest) {
	this.molecule = molecule;
	this.rest = rest;
	
	this.equals = function(expr) {
		if(expr===undefined || !('contains' in expr)) 
			return false;
		
		var molp = [this.molecule, this.rest];
		while('contains' in molp[1]) {
			if(!expr.contains(molp[0]))
				return false;
			molp[0] = molp[1].molecule;
			molp[1] = molp[1].rest;
		}
		if(!expr.contains(molp[0]))
			return false;
		if(!expr.contains(molp[1]))
			return false;
		
		molp = [expr.molecule, expr.rest];
		while('contains' in molp[1]) {
			if(!this.contains(molp[0]))
				return false;
			molp[0] = molp[1].molecule;
			molp[1] = molp[1].rest;
		}
		if(!this.contains(molp[0]))
			return false;
		if(!this.contains(molp[1]))
			return false;
		
		return true;
	}
	this.toHTML = function() {
		return this.molecule.toHTML() + " + " + this.rest.toHTML();
	}
	this.contains = function(mol) {
		if('contains' in this.rest)
			return this.molecule.equals(mol) || this.rest.contains(mol);
		else
			return this.molecule.equals(mol) || this.rest.equals(mol);
	}
}

Molecule.prototype = new Expression();
Molecule.prototype.constructor = Molecule;

function Molecule(coeff,formula) {
	this.coeff = coeff;
	this.formula = formula;
	
	this.equals = function(expr) {
		if(expr===undefined) 
			return false;
		
		return this.coeff == expr.coeff && this.formula.equals(expr.formula);
	}
	this.toHTML = function() {
		return this.coeff+this.formula.toHTML();
	}
}

Formula.prototype = new Expression();
Formula.prototype.constructor = Formula;

function Formula(unit,rest,phase) {
	this.unit = unit;
	this.rest = rest;
	this.suffix = rest.suffix;
	this.phase = phase;
	this.paren = false;
	
	this.equals = function(expr) {
		if(expr===undefined || expr.phase != this.phase || !('elemCounts' in expr)) 
			return false;
		
		var counts = this.elemCounts();
		var counts2 = expr.elemCounts();
		for(eid in counts)
			if(!(eid in counts2) || counts2[eid] != counts[eid])
				return false;
		for(eid in counts2)
			if(!(eid in counts) || counts2[eid] != counts[eid])
				return false;
		return this.suffix.ion == expr.suffix.ion;
	}
	this.toHTML = function() {
		return (this.paren ? "(" : "")+this.unit.toHTML()+this.rest.toHTML()+(this.paren ? ")" : "")+(this.phase.length > 0 ? " "+this.phase : "");
	}
	this.elemCounts = function() {
		var counts = unit.elemCounts();
		var counts2 = rest.elemCounts();
		for(eid in counts2) {
			if(eid in counts)
				counts[eid] += counts2[eid];
			else
				counts[eid] = counts2[eid];
		}
		return counts;
	}
}

Unit.prototype = new Expression();
Unit.prototype.constructor = Unit;

function Unit(group,suffix) {
	this.group = group;
	this.suffix = suffix;
	this.paren = false;
	
	this.equals = function(expr) {
		if(expr===undefined || !('elemCounts' in expr)) 
			return false;
		
		var counts = this.elemCounts();
		var counts2 = expr.elemCounts();
		for(eid in counts)
			if(!(eid in counts2) || counts2[eid] != counts[eid])
				return false;
		for(eid in counts2)
			if(!(eid in counts) || counts2[eid] != counts[eid])
				return false;
		return suffix.ion == expr.suffix.ion;
	}
	this.toHTML = function() {
		return (this.paren ? "(" : "")+this.group.toHTML()+(this.paren ? ")" : "") + this.suffix.toHTML();
	}
	this.elemCounts = function() {
		var counts = group.elemCounts();
		for(eid in counts)
			counts[eid] *= this.suffix.num;
		return counts;
	}
}

Suffix.prototype = new Expression();
Suffix.prototype.constructor = Suffix;

function Suffix(num,ion) {
	this.num = num;
	this.ion = ion;
	
	this.equals = function(expr) {
		if(expr===undefined) 
			return false;
		
		return this.num == expr.num && this.ion == expr.ion;
	}
	this.toHTML = function() {
		return (num > 1 ? "<sub>"+num+"</sub>" : "") + ("<sup>"+ion+"</sup>");
	}
}

Element.prototype = new Expression();
Element.prototype.constructor = Element;

function Element(elem) {
	this.elem = elem;
	
	this.equals = function(expr) {
		if(expr===undefined) 
			return false;
		
		return expr.elem == this.elem;
	}
	this.toHTML = function() {
			return this.elem;
	}
	this.elemCounts = function() {
		counts = {};
		counts[this.elem] = 1;
		return counts;
	}
}