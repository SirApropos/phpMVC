#include "Problem14.h"

Problem14::Problem14(void)
{
	setName("Problem 14");
	target = 1000000;
	cacheSize = target;
	cache = new int[cacheSize];
	for(int i=0; i<cacheSize; i++){
		cache[i] = NULL;
	}
}


__int64 Problem14::run(void){
	__int64 result = 0;
	__int64 longest = 0;

	for(int i=1;i<target;i++){
		__int64 temp = runSequence(i);
		if(temp > longest){
			result = i;
			longest = temp;
		}
	}
	return result;
}

__int64 Problem14::runSequence(__int64 pos){
	__int64 steps = 1;
	if(pos >1){
		if(pos <= cacheSize && cache[pos] != NULL){
			steps += cache[pos];
		}else{
			if(pos & 1){ //Check if odd
				steps += runSequence((((pos << 1) + pos + 1) >> 1)) + 1; //(3n+1)/2
			}else{
				steps += runSequence(pos>>1);
			}
			if(pos <= cacheSize){
				cache[pos] = steps;
			}
		}
	}
	return steps;
}


Problem14::~Problem14(void)
{
}
