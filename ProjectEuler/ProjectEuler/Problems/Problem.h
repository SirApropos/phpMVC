#pragma once
#include <string>

#ifndef ProblemDef
#define ProblemDef
namespace Problems{
	class Problem
	{
	public:
		Problem(void);
		std::string getName(void);
		virtual __int64 run() = 0;
	protected:
		void setName(std::string name);
	private:
		char *name;
	};
}
#endif