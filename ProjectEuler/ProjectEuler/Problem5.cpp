#include "Problem5.h"


Problem5::Problem5(void)
{
	target = 20;
	setName("Problem 5");
}


int Problem5::run(void){
	List<int> * primes = &List<int>();
	List<int> * allfactors = &List<int>();
	for(int i=20;i>=2;i--){
		List<int> * factors = &EulerUtils::findFactors(i);
		List<int> * used = &List<int>();
		factors->foreach([&](int j){			
			int offset = 0;
			while(true){
				int index = allfactors->indexOf(j, offset);
				if(index != -1){
					if(used->contains(index)){
						offset = index+1;
					}else{
						used->add(index);
						break;
					}
				}else{
					used->add(allfactors->size());
					allfactors->add(j);
					break;
				}
			}
			return false;
		});
	}
	int total = 1;
	allfactors->foreach([&](int i){
		total *= i;
		return false;
	});
	return total;
}