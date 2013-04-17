#pragma once
#include "../stdafx.h"
#include "problem.h"
#ifndef Problem15def
#define Problem15def
using namespace Problems;
namespace Problems{
	class Problem15 : public Problem
	{
	private:
		int size;
		class Node{
			__int64 paths;
			Pair<int, int> * pos;			
		public:
			void propagate(Node *** nodes, int size);
			Node(Pair<int,int> * pos);
			~Node(void);
			__int64 getPaths(void);
		};
		Node *** nodes;
	public:
		_int64 run(void);
		Problem15(void);
		~Problem15(void);
	};
}
#endif