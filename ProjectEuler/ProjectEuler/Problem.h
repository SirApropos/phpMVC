#pragma once
#include <string>

#ifndef Problem
namespace Problems{
	class Problem
	{
	public:
		Problem(void);
		std::string getName(void);
		virtual int run() = 0;
	protected:
		void setName(std::string name);
	private:
		std::string name;
	};
}
#endif