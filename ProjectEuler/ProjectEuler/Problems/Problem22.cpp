#include "Problem22.h"


Problem22::Problem22(void)
{
}

Problem22::~Problem22(void)
{
}

__int64 Problem22::run(void){
	List<std::string *> data = List<std::string *>();
	data.scaleTo(5200);
	EulerUtils::readFile("./Problems/Data/Problem22.txt", data);
	data.sort(EulerUtils::getStringPointerComparator());
	int i = 1;
	__int64 result = 0;
	data.vforeach([&](std::string * name){
		result += getValue(*name) * i++;
	});
	return result;
}

__int64 Problem22::getValue(std::string str){
	__int64 result = 0;
	for(int i=0;i<str.size();i++){
		result += getCharValue(str[i]);
	}
	return result;
}

int Problem22::getCharValue(char c){
	return ((int)c) - ((int)'A') + 1;
}
