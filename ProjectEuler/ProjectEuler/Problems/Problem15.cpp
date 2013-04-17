#include "Problem15.h"

Problem15::Problem15(void)
{
	setName("Problem 15");
	size = 20;
	nodes = new Node ** [size];
	for(int x=0;x<size;x++){
		nodes[x] = new Node * [size];
		for(int y=0;y<size;y++){
			nodes[x][y] = new Node(new Pair<int,int>(x,y));
		}
	}
}

Problem15::~Problem15(void)
{
}


Problem15::Node::Node(Pair<int, int> * pos){
	paths = 0;
	this->pos = new Pair<int,int>(*pos);
}
Problem15::Node::~Node(void){}

__int64 Problem15::Node::getPaths(void){
	return paths;
}

void Problem15::Node::propagate(Node *** nodes, int size){
	int x = pos->getKey();
	int y = pos->getValue();
	if(x < size && y < size){
		paths++;
	}
	if(x < (size - 1)){
		nodes[x+1][y]->paths += paths;
	}
	if(y < (size - 1)){
		nodes[x][y+1]->paths += paths;
	}
}

__int64 Problem15::run(void){
	for(int x=0;x<size;x++){
		for(int y=0;y<size;y++){
			nodes[x][y]->propagate(nodes,size);
		}
	}
	return nodes[size-1][size-1]->getPaths()+1;
}