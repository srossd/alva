function Expression() {
    this.equals = function(expr) {};
	this.toLatex = function() {};
}

Num.prototype = new Expression();
Num.prototype.constructor = Num;

function Num(n) {
	this.num = n;
	
	this.equals = function(n2) {
		if(n2===undefined || !('num' in n2))
			return false;
		
		return this.num == n2.num
	}
	this.toLatex = function() {
		return this.num;
	}
}

Var.prototype = new Expression();
Var.prototype.constructor = Var;

function Var(s) {
	this.name = s;
	
	this.equals = function(v2) {
		if(v2===undefined || !('name' in v2)) 
			return false;
		
		return this.name == v2.name;
	}
	this.toLatex = function() {
		return this.name;
	}
}

Paren.prototype = new Expression();
Paren.prototype.constructor = Paren;

function Paren(e1) {
	this.e = e1;
	
	this.equals = function(p2) {
		if(p2===undefined || !('e' in p2)) 
			return false;
		
		return this.e.equals(p2.e);
	}
	this.toLatex = function() {
		return "("+this.e.toLatex()+")";
	}
}

Equality.prototype = new Expression();
Equality.prototype.constructor = Equality;

function Equality(a1,a2,symbol) {
	this.e1 = a1;
	this.e2 = a2;
	this.sym = symbol;
	
	this.equals = function(eq2) {
		if(eq2===undefined || !('e1' in eq2 && 'sym' in eq2)) 
			return false;
		
		return this.sym == eq2.sym && ((eq2.e1.equals(this.e1) && eq2.e2.equals(this.e2)) || (eq2.e2.equals(this.e1) && eq2.e1.equals(this.e2)));
	}
	this.toLatex = function() {
		if(this.sym == "<=")
			return this.e1.toLatex()+" \\le "+this.e2.toLatex();
		else if(this.sym == ">=")
			return this.e1.toLatex()+" \\ge "+this.e2.toLatex();
		else
			return this.e1.toLatex()+" "+this.sym+" "+this.e2.toLatex();
	}
}

Additive.prototype = new Expression();
Additive.prototype.constructor = Additive;

function Additive(a1,a2,symbol) {
	this.e1 = a1;
	this.e2 = a2;
	this.sym = symbol;
	
	this.equals = function(add2) {
		if(add2===undefined || !('e1' in add2 && 'sym' in add2)) 
			return false;
		
		return this.sym == add2.sym && ((add2.e1.equals(this.e1) && add2.e2.equals(this.e2)) || (add2.e2.equals(this.e1) && add2.e1.equals(this.e2)));
	}
	this.toLatex = function() {
		return this.e1.toLatex()+" "+this.sym+" "+this.e2.toLatex();
	}
}

Multiplicative.prototype = new Expression();
Multiplicative.prototype.constructor = Multiplicative;

function Multiplicative(m1,m2,sym,exp) {
	this.e1 = m1;
	this.e2 = m2;
	this.type = sym;
	this.times = exp;
	
	this.equals = function(mul2) {
		if(mul2===undefined || !('e1' in mul2 && 'sym' in mul2)) 
			return false;
		
		return mul2.symbol == this.symbol && ((mul2.e1.equals(this.e1) && mul2.e2.equals(this.e2)) || (mul2.e2.equals(this.e1) && mul2.e1.equals(this.e2)));
	}
	this.toLatex = function() {
		if(this.type == "*")
			return (this.times ? this.e1.toLatex()+" \\times "+this.e2.toLatex() : this.e1.toLatex()+" "+this.e2.toLatex());
		else
			return "\\frac{"+this.e1.toLatex()+"}{"+this.e2.toLatex()+"}";
	}
}

Exponential.prototype = new Expression();
Exponential.prototype.constructor = Exponential;

function Exponential(ex1,ex2) {
	this.e1 = ex1;
	this.e2 = ex2;
	
	this.equals = function(exp2) {
		if(exp2===undefined || !('e1' in add2 && 'e2' in add2)) 
			return false;
		
		return exp2.e1.equals(this.e1) && exp2.e2.equals(this.e2);
	}
	this.toLatex = function() {
			return this.e1.toLatex()+"^{"+this.e2.toLatex()+"}";
	}
}