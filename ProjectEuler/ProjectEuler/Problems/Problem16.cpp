#include "Problem16.h"

Problem16::Problem16(void)
{
	setName("Problem 16");
	target = 1000;
	//buckets = new List<unsigned __int64>();
	//buckets->add(1);
}

__int64 Problem16::run(void){
	std::stringstream str;
	unsigned __int64 current = 1;
	__int64 buckets = 0;
	for(int i=0;i<target;i++){
		buckets *= 2;
		current *= 2;
		if(current > _I64_MAX){
			buckets +=1;
			current -= _I64_MAX;
		}
		std::cout << "i: " << i << std::endl;
		std::cout << "Buckets: " << buckets << std::endl;
		std::cout << "Current: " << current << std::endl;
		getchar();
	}
	std::stringstream maxIntStream;
	maxIntStream << _I64_MAX;
	std::stringstream currentStream;
	currentStream << current;
	std::string maxIntStr = maxIntStream.str();
	std::string currentStr = currentStream.str();
	List<int> * numbers = new List<int>();
	int carry = 0;
	for(int i=maxIntStr.size();i>=0;i++){
		int temp = carry;
		for(int j = 0;j<buckets;j++){
			temp += ((int)maxIntStr[i] - (int)'0');
		}
		if((unsigned int)i < currentStr.size()){
			temp += ((int)maxIntStr[i + maxIntStr.size() - currentStr.size()] - (int)'0');
		}
		while(temp > 10){
			carry++;
			temp -= 10;
		}
		numbers->add(temp);
	}
	while(carry > 0){
		int temp = carry;
		carry = 0;
		while(temp > 10){
			carry++;
			temp -= 10;
		}
		numbers->add(temp);
	}
	__int64 result = 0;
	numbers->foreach([&](int num){
		std::cout << "Character: " << num << std::endl;
		result += num;
		return false;
	});
	return result;
}

void Problem16::nextStep(void){
	buckets->foreach([&](unsigned __int64 bucket){
		bucket *= 2;
		if(bucket > _I64_MAX){
			
		}
		return false;
	});
}


Problem16::~Problem16(void)
{
}
