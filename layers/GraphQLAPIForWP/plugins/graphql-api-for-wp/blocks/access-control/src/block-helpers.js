import {
	ACCESS_CONTROL_BLOCK_CATEGORY,
} from './block-settings.js';

/**
 * Check that this block has Access Control Rule innerBlocks (i.e. they have not been all disabled)
 * To find out, check that block types registered in category are at least 2 (this block is 1, then is the rules)
 */
const doesAccessControlBlockNotHaveRuleBlocks = () => {
	return wp.blocks.getBlockTypes().filter( blockType => blockType.category == ACCESS_CONTROL_BLOCK_CATEGORY).length == 1
}

export { doesAccessControlBlockNotHaveRuleBlocks };
