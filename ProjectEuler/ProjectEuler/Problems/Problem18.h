#pragma once
#include "../stdafx.h"
#include "Problem.h"
using namespace Problems;
namespace Problems{
	class Problem18 :
		public Problem
	{
	private:
		std::string * target;
	protected:
		void setTarget(std::string filename);
	public:
		Problem18(void);
		__int64 run(void);
	};
}