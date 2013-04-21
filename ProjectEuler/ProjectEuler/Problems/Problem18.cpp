#include "Problem18.h"

Problem18::Problem18(void)
{
	setTarget("./Problems/Data/Problem18.txt");
}

void Problem18::setTarget(std::string target){
	this->target = new std::string(target);
}

__int64 Problem18::run(void){
	List<std::string *> data = EulerUtils::readFile(*target);
	List<int> ** lines = new List<int> * [data.size()];
	{int j = 0;
		data.vforeach([&](std::string * line){
			List<int> * list = new List<int>();
			const char * chars = line->c_str();
			for(int i=0;i<line->length();i+=3){
				char num [2] = {chars[i],chars[i+1]};
				list->add(atoi(num));			
			}
			lines[j++] = list;
		});
	}
	for(int i=1;i<data.size();i++){
		List<int> * current = lines[data.size()-i];
		List<int> * previous = lines[data.size()-i - 1];
		for(int j=0;j<current->size()-1;j++){
			int add = current->get(j) > current->get(j+1) ? current->get(j) : current->get(j+1);
			previous->set(j, previous->get(j)+add);
		}
	}

	return lines[0]->first();
}